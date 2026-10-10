import { useCallback, useEffect, useMemo, useState } from "react";

import {
  acceptSubscriptionRequest,
  getDashboardTrainees,
  getPendingSubscriptionRequests,
  rejectSubscriptionRequest,
} from "../services/dashboardService";

const useCoachDashboard = () => {
  const [trainees, setTrainees] = useState([]);
  const [pendingRequests, setPendingRequests] = useState([]);

  const [loading, setLoading] = useState(true);
  const [actionLoadingId, setActionLoadingId] = useState(null);

  const [error, setError] = useState("");

  /*
  |--------------------------------------------------------------------------
  | Load dashboard data
  |--------------------------------------------------------------------------
  */

  const fetchDashboardData = useCallback(async () => {
    try {
      setLoading(true);
      setError("");

      const [traineesResponse, pendingResponse] =
        await Promise.all([
          getDashboardTrainees(),
          getPendingSubscriptionRequests(),
        ]);

      setTrainees(
        Array.isArray(traineesResponse?.data)
          ? traineesResponse.data
          : [],
      );

      setPendingRequests(
        Array.isArray(pendingResponse?.data)
          ? pendingResponse.data
          : [],
      );
    } catch (error) {
      console.error(
        "Error loading coach dashboard:",
        error,
      );

      setError(
        error.response?.data?.message ||
          "حدث خطأ أثناء تحميل بيانات لوحة التحكم",
      );
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchDashboardData();
  }, [fetchDashboardData]);

  /*
  |--------------------------------------------------------------------------
  | Accept
  |--------------------------------------------------------------------------
  */

  const acceptRequest = async (subscriptionId) => {
    try {
      setActionLoadingId(subscriptionId);

      await acceptSubscriptionRequest(subscriptionId);

      await fetchDashboardData();

      return true;
    } catch (error) {
      console.error(
        "Error accepting subscription:",
        error,
      );

      setError(
        error.response?.data?.message ||
          "حدث خطأ أثناء قبول طلب الاشتراك",
      );

      return false;
    } finally {
      setActionLoadingId(null);
    }
  };

  /*
  |--------------------------------------------------------------------------
  | Reject
  |--------------------------------------------------------------------------
  */

  const rejectRequest = async (subscriptionId) => {
    try {
      setActionLoadingId(subscriptionId);

      await rejectSubscriptionRequest(subscriptionId);

      setPendingRequests((currentRequests) =>
        currentRequests.filter(
          (request) => request.id !== subscriptionId,
        ),
      );

      return true;
    } catch (error) {
      console.error(
        "Error rejecting subscription:",
        error,
      );

      setError(
        error.response?.data?.message ||
          "حدث خطأ أثناء رفض طلب الاشتراك",
      );

      return false;
    } finally {
      setActionLoadingId(null);
    }
  };

  /*
  |--------------------------------------------------------------------------
  | Dashboard statistics
  |--------------------------------------------------------------------------
  */

  const statistics = useMemo(() => {
    const totalTrainees = trainees.length;

    const activePlans = trainees.filter(
      (subscription) =>
        subscription?.workout_plan?.status === "active",
    ).length;

    const withoutPlan = trainees.filter(
      (subscription) => !subscription?.workout_plan,
    ).length;

    const pendingCount = pendingRequests.length;

    return {
      totalTrainees,
      activePlans,
      withoutPlan,
      pendingCount,
    };
  }, [trainees, pendingRequests]);

  /*
  |--------------------------------------------------------------------------
  | Latest trainees
  |--------------------------------------------------------------------------
  */

  const recentTrainees = useMemo(() => {
    return trainees.slice(0, 4);
  }, [trainees]);

  return {
    trainees,
    pendingRequests,
    recentTrainees,

    statistics,

    loading,
    error,

    actionLoadingId,

    acceptRequest,
    rejectRequest,
    refreshDashboard: fetchDashboardData,
  };
};

export default useCoachDashboard;