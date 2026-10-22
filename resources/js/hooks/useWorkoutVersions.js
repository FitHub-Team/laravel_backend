import { useCallback, useEffect, useState } from "react";
import workoutPlanService from "../services/workoutPlanService";

export default function useWorkoutVersions(traineeId) {
    const [isSelectorOpen, setIsSelectorOpen] = useState(false);
    const [activeModal, setActiveModal] = useState(null);

    const [versions, setVersions] = useState([]);
    const [selectedVersion, setSelectedVersion] = useState(null);
    const [selectedPlanId, setSelectedPlanId] = useState(null);

    const [isLoading, setIsLoading] = useState(false);
    const [isDetailsLoading, setIsDetailsLoading] = useState(false);
    const [error, setError] = useState(null);

    const openVersions = useCallback(() => {
        if (!traineeId) return;

        setError(null);
        setSelectedVersion(null);
        setSelectedPlanId(null);
        setActiveModal(null);
        setIsSelectorOpen(true);
    }, [traineeId]);

    const openWorkoutVersions = useCallback(() => {
        if (!traineeId) return;

        setIsSelectorOpen(false);
        setSelectedVersion(null);
        setSelectedPlanId(null);
        setError(null);
        setActiveModal("workout");
    }, [traineeId]);

    const openNutritionVersions = useCallback(() => {
        if (!traineeId) return;

        setIsSelectorOpen(false);
        setError(null);
        setActiveModal("nutrition");
    }, [traineeId]);

    const closeVersions = useCallback(() => {
        setIsSelectorOpen(false);
        setActiveModal(null);
        setSelectedVersion(null);
        setSelectedPlanId(null);
        setError(null);
    }, []);

    const backToVersions = useCallback(() => {
        setSelectedVersion(null);
        setSelectedPlanId(null);
        setError(null);
    }, []);

    const fetchVersions = useCallback(async () => {
        if (!traineeId) return [];

        setIsLoading(true);
        setError(null);

        try {
            const data =
                await workoutPlanService.getWorkoutVersions(traineeId);

            return Array.isArray(data) ? data : [];
        } catch (err) {
            setError(
                err.response?.data?.message ||
                err.message ||
                "تعذر تحميل إصدارات التمارين"
            );
            return [];
        } finally {
            setIsLoading(false);
        }
    }, [traineeId]);

    // Load versions once each time the workout modal is opened.
    useEffect(() => {
        if (activeModal !== "workout" || !traineeId) return;

        let cancelled = false;

        const load = async () => {
            setIsLoading(true);
            setError(null);
            setVersions([]);

            try {
                const data =
                    await workoutPlanService.getWorkoutVersions(traineeId);

                if (!cancelled) {
                    setVersions(Array.isArray(data) ? data : []);
                }
            } catch (err) {
                if (!cancelled) {
                    setError(
                        err.response?.data?.message ||
                        err.message ||
                        "تعذر تحميل إصدارات التمارين"
                    );
                }
            } finally {
                if (!cancelled) setIsLoading(false);
            }
        };

        load();

        return () => {
            cancelled = true;
        };
    }, [activeModal, traineeId]);

    // Save only the selected plan ID.
    const fetchVersionDetails = useCallback((planId) => {
        if (!planId) return;

        setError(null);
        setSelectedVersion(null);
        setSelectedPlanId(planId);
    }, []);

    // Load details of selected version.
    useEffect(() => {
        if (
            activeModal !== "workout" ||
            !traineeId ||
            !selectedPlanId
        ) {
            return;
        }

        let cancelled = false;

        const load = async () => {
            setIsDetailsLoading(true);
            setError(null);

            try {
                const plan =
                    await workoutPlanService.getWorkoutVersionDetails(
                        traineeId,
                        selectedPlanId
                    );

                if (!cancelled) {
                    setSelectedVersion(plan);
                }
            } catch (err) {
                if (!cancelled) {
                    setError(
                        err.response?.data?.message ||
                        err.message ||
                        "تعذر تحميل تفاصيل الإصدار"
                    );
                }
            } finally {
                if (!cancelled) setIsDetailsLoading(false);
            }
        };

        load();

        return () => {
            cancelled = true;
        };
    }, [activeModal, traineeId, selectedPlanId]);

    // Reset when switching trainee.
    useEffect(() => {
        setIsSelectorOpen(false);
        setActiveModal(null);
        setVersions([]);
        setSelectedVersion(null);
        setSelectedPlanId(null);
        setError(null);
    }, [traineeId]);

    return {
        isSelectorOpen,
        activeModal,

        openVersions,
        openWorkoutVersions,
        openNutritionVersions,
        closeVersions,

        versions,
        selectedVersion,
        selectedPlanId,

        fetchVersions,
        fetchVersionDetails,
        backToVersions,

        isLoading,
        isDetailsLoading,
        error,
    };
}