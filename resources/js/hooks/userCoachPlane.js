
import { useCallback, useEffect, useState } from "react";
import { getCoachPlans } from "../services/coachPlansService";

export default function useCoachPlans() {
    const [plans, setPlans] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const refreshPlans = useCallback(async () => {
        setLoading(true);
        setError(null);

        try {
            const response = await getCoachPlans();

            if (response.status === false) {
                throw new Error(
                    response.message || "فشل جلب الخطط"
                );
            }

            setPlans(
                Array.isArray(response.data)
                    ? response.data
                    : []
            );

            return true;
        } catch (err) {
            setError(
                err.response?.data?.message ||
                err.message ||
                "حدث خطأ أثناء جلب الخطط"
            );

            return false;
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        refreshPlans();
    }, [refreshPlans]);

    return {
        plans,
        loading,
        error,
        refreshPlans,
    };
}
