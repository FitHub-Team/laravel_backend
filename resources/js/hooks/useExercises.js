
import { keepPreviousData, useQuery } from "@tanstack/react-query";
import { getExercises } from "../services/exerciseService";

const useExercises = (enabled = true, params = {}) => {
  const {
    page = 1,
    search = "",
    muscle_group = "",
    difficulty_level = "",
  } = params;

  return useQuery({
    queryKey: [
      "exercises",
      page,
      search,
      muscle_group,
      difficulty_level,
    ],

    queryFn: () =>
      getExercises({
        page,
        search,
        muscle_group,
        difficulty_level,
      }),

    enabled,

    staleTime: 1000 * 60 * 5,

    placeholderData: keepPreviousData,
  });
};

export default useExercises;
