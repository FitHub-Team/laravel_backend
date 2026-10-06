import { useQuery } from "@tanstack/react-query";
import { getExercises } from "../services/exerciseService";

const useExercises = (enabled = true) => {
  return useQuery({
    queryKey: ["exercises"],
    queryFn: getExercises,
    enabled,
    staleTime: 1000 * 60 * 5,
  });
};

export default useExercises;