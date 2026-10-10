function StatCard({
  title,
  value,
  icon: Icon,
  subtitle,
  color,
  note,
  textColor,
  NoteColor,
}) {
  return (
    <div className="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-full hover:shadow-md transition-shadow">
      <div className="flex justify-between items-start mb-4">
        <div>
          <h6
            className={`font-semibold text-sm mb-2 ${
              textColor ?? "text-gray-500"
            }`}
          >
            {title}
          </h6>

          <span className="text-3xl font-bold text-gray-900">
            {value}
          </span>
        </div>

        <div
          className={`w-11 h-11 rounded-xl flex items-center justify-center ${
            color ??
            "bg-green-50 text-[#407437]"
          }`}
        >
          {Icon && (
            <Icon
              size={20}
              strokeWidth={2}
            />
          )}
        </div>
      </div>

      <div className="mt-2">
        {note && (
          <span
            className={`inline-flex text-xs px-2.5 py-1 rounded-lg font-semibold ${
              NoteColor ??
              "bg-green-50 text-green-700"
            }`}
          >
            {note}
          </span>
        )}

        {subtitle && (
          <p className="text-xs text-gray-400 mt-3 leading-5">
            {subtitle}
          </p>
        )}
      </div>
    </div>
  );
}

export default StatCard;