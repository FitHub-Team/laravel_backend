import { useEffect, useState } from "react";
import { getTraineeProfile } from "../services/traineeService";

const useTraineeProfile = (traineeId) => {
  const [trainee, setTrainee] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    if (!traineeId) {
      setError("رقم المشترك غير موجود");
      setLoading(false);
      return;
    }

    const fetchTrainee = async () => {
      try {
        setLoading(true);
        setError("");

        const response =
          await getTraineeProfile(traineeId);

        console.log(
          "Trainee profile response:",
          response,
        );

        setTrainee(response?.data ?? null);
      } catch (error) {
        console.error(
          "Error loading trainee profile:",
          error,
        );

        setError(
          error.response?.data?.message ||
            "حدث خطأ أثناء تحميل بيانات المشترك",
        );
      } finally {
        setLoading(false);
      }
    };

    fetchTrainee();
  }, [traineeId]);

  return {
    trainee,
    loading,
    error,
  };
};

export default useTraineeProfile;