
import { useEffect, useState } from "react";
import workoutPlanService from "../services/workoutPlanService";

const errorMessage = (err, fallback) => {
    const errors = err?.response?.data?.errors;

    if (errors && typeof errors === "object") {
        const messages = Object.values(errors)
            .flat()
            .filter(Boolean);

        if (messages.length) {
            return messages.join(" | ");
        }
    }

    return (
        err?.response?.data?.message ||
        err?.message ||
        fallback
    );
};

export default function useCoachTrainees(traineeId = null) {
    const [trainees, setTrainees] = useState([]);
    const [selectedTrainee, setSelectedTrainee] = useState(null);

    const [isTraineesLoading, setIsTraineesLoading] =
        useState(true);

    const [traineesError, setTraineesError] = useState(null);

    // Fetch trainees from the existing Laravel API
    useEffect(() => {
        let active = true;

        setIsTraineesLoading(true);
        setTraineesError(null);

        workoutPlanService
            .getTrainees()
            .then((data) => {
                if (!active) return;

                setTrainees(
                    Array.isArray(data) ? data : []
                );
            })
            .catch((err) => {
                if (!active) return;

                setTrainees([]);
                setTraineesError(
                    errorMessage(
                        err,
                        "تعذّر تحميل المشتركين"
                    )
                );
            })
            .finally(() => {
                if (active) {
                    setIsTraineesLoading(false);
                }
            });

        return () => {
            active = false;
        };
    }, []);

    // Select a trainee without loading a workout plan
    const selectTrainee = (subscription) => {
        if (!subscription?.trainee?.id) return;

        setSelectedTrainee(subscription);
    };

    // Automatically select trainee from URL
    useEffect(() => {
        if (!traineeId || trainees.length === 0) return;

        const subscription = trainees.find(
            (item) =>
                String(item.trainee?.id) === String(traineeId)
        );

        if (subscription) {
            setSelectedTrainee(subscription);
        }
    }, [traineeId, trainees]);

    return {
        trainees,
        selectedTrainee,
        selectTrainee,
        isTraineesLoading,
        traineesError,
    };
}
