import { useEffect, useState } from "react";
import { getCoachTrainees } from "../services/traineeService";

const useTrainees = () => {
  const [trainees, setTrainees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const [search, setSearch] = useState("");
  const [submittedSearch, setSubmittedSearch] = useState("");

  useEffect(() => {
    const fetchTrainees = async () => {
      try {
        setLoading(true);
        setError("");

        const data = await getCoachTrainees(submittedSearch);

        console.log("Trainees response:", data);

        setTrainees(data?.data ?? []);
      } catch (error) {
        console.error("Error loading trainees:", error);

        setError("حدث خطأ أثناء تحميل المتدربين");
      } finally {
        setLoading(false);
      }
    };

    fetchTrainees();
  }, [submittedSearch]);

  const submitSearch = () => {
    setSubmittedSearch(search);
  };

  return {
    trainees,
    loading,
    error,

    search,
    setSearch,

    submitSearch,
  };
};

export default useTrainees;