import { useCallback, useEffect, useState } from 'react';
import customExerciseService from '../services/customExerciseService';

export default function useCustomExercises(initialFilters = {}) {
    const [exercises, setExercises] = useState([]);
    const [meta, setMeta] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [filters, setFilters] = useState(initialFilters);

    const fetchExercises = useCallback(async (overrideFilters) => {
        setLoading(true);
        setError(null);
        try {
            const params = { ...filters, ...(overrideFilters || {}) };
            // إزالة القيم الفارغة
            Object.keys(params).forEach((key) => {
                if (params[key] === '' || params[key] === null || params[key] === undefined) {
                    delete params[key];
                }
            });

            const result = await customExerciseService.getCustomExercises(params);
            setExercises(result.data || []);
            setMeta(result.meta || null);
        } catch (err) {
            setError(err?.response?.data?.message || 'فشل تحميل التمارين');
        } finally {
            setLoading(false);
        }
    }, [filters]);

    useEffect(() => {
        fetchExercises();
    }, [fetchExercises]);

    const createExercise = async (data) => {
        const result = await customExerciseService.createCustomExercise(data);
        await fetchExercises();
        return result;
    };

    const updateExercise = async (id, data) => {
        const result = await customExerciseService.updateCustomExercise(id, data);
        await fetchExercises();
        return result;
    };

    const deleteExercise = async (id) => {
        const result = await customExerciseService.deleteCustomExercise(id);
        await fetchExercises();
        return result;
    };

    const refresh = () => fetchExercises();

    return {
        exercises,
        meta,
        loading,
        error,
        filters,
        setFilters,
        fetchExercises,
        createExercise,
        updateExercise,
        deleteExercise,
        refresh,
    };
}