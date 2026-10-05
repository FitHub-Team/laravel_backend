const FilterPill = ({
  active,
  onClick,
  children,
  variant = "default",
}) => {
  const baseStyles =
    "inline-flex items-center h-[30px] px-3 rounded-[10px] text-xs font-semibold transition-all whitespace-nowrap";

  // الحالة النشطة — أخضر من الديزاين سيستم
  const activeStyles =
    "bg-green-700 text-white cursor-pointer ";

  // الحالة غير النشطة — تدرج أخضر فاتح
  const inactiveStyles =
    variant === "soft"
      ? "bg-white border border-[#D1D5DB] text-[#314158] cursor-pointer"
      : "bg-white border border-[#D1D5DB] text-[#314158] cursor-pointer ";

  return (
    <button
      type="button"
      onClick={onClick}
      className={`${baseStyles} ${
        active ? activeStyles : inactiveStyles
      }`}
    >
      {children}
    </button>
  );
};

export default FilterPill;