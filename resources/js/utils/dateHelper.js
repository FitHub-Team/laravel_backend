export const formatLastUpdated = (date) => {
  if (!date) return "لا يوجد";

  const updatedDate = new Date(date);
  const today = new Date();

  const todayStart = new Date(
    today.getFullYear(),
    today.getMonth(),
    today.getDate()
  );

  const updatedStart = new Date(
    updatedDate.getFullYear(),
    updatedDate.getMonth(),
    updatedDate.getDate()
  );

  const diffTime = todayStart - updatedStart;
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays === 0) return "اليوم";
  if (diffDays === 1) return "أمس";

  if (diffDays > 1 && diffDays < 7) {
    return `منذ ${diffDays} أيام`;
  }

  return updatedDate.toLocaleDateString("ar", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};