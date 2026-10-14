import {
  Eye,
  AlertTriangle,
  Activity,
} from "lucide-react";

function ClientCard({
  name,
  progress = 0,
  status,
  reason,
  activity,
  onView,
}) {
  const initial = name?.charAt(0) ?? "؟";

  const progressColor =
    progress >= 70
      ? "bg-green-500"
      : progress >= 45
        ? "bg-amber-500"
        : "bg-red-500";

  return (
    <div className="border border-gray-100 rounded-2xl p-4 hover:bg-gray-50/60 transition-colors">
      <div className="flex flex-col md:flex-row md:items-start justify-between gap-4">
        {/* User */}
        <div className="flex gap-3 flex-1">
          <div className="w-11 h-11 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold shrink-0">
            {initial}
          </div>

          <div className="flex-1 min-w-0">
            <div className="flex flex-wrap items-center gap-2">
              <h3 className="font-bold text-gray-900 text-sm">
                {name}
              </h3>

              <span className="bg-amber-50 text-amber-700 rounded-full px-2 py-1 text-[10px] font-semibold">
                {status}
              </span>
            </div>

            <div className="flex items-start gap-2 mt-3 bg-amber-50/70 rounded-xl px-3 py-2.5">
              <AlertTriangle
                size={14}
                className="text-amber-600 mt-0.5 shrink-0"
              />

              <p className="text-xs text-gray-600 leading-5">
                {reason}
              </p>
            </div>

            <div className="flex items-center gap-1.5 text-[11px] text-gray-400 mt-3">
              <Activity size={13} />
              {activity}
            </div>
          </div>
        </div>

        {/* Progress */}
        <div className="md:w-40">
          <div className="flex items-center justify-between text-xs mb-2">
            <span className="text-gray-400">
              الالتزام
            </span>

            <span className="font-bold text-gray-700">
              {progress}%
            </span>
          </div>

          <div className="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div
              className={`h-full rounded-full transition-all ${progressColor}`}
              style={{
                width: `${Math.min(progress, 100)}%`,
              }}
            />
          </div>

          <button
            type="button"
            onClick={onView}
            className="
              w-full
              mt-4
              flex
              items-center
              justify-center
              gap-2
              bg-green-700
              hover:bg-green-800
              text-white
              text-xs
              font-semibold
              rounded-xl
              px-3
              py-2.5
              transition-colors
            "
          >
            <Eye size={15} />

            عرض الملف
          </button>
        </div>
      </div>
    </div>
  );
}

export default ClientCard;