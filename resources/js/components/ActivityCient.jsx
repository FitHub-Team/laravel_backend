import { CheckCircle2 } from "lucide-react";

function ActivityCient({
  name,
  title,
  status,
  timeAgo,
}) {
  const initial = name?.charAt(0) ?? "؟";

  return (
    <div className="py-4 border-b border-gray-100 last:border-b-0">
      <div className="flex items-start gap-3">
        <div className="relative shrink-0">
          <div className="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm">
            {initial}
          </div>

          <span className="absolute -bottom-1 -left-1 w-5 h-5 rounded-full bg-green-600 text-white flex items-center justify-center border-2 border-white">
            <CheckCircle2 size={11} />
          </span>
        </div>

        <div className="flex-1 min-w-0">
          <p className="text-sm text-gray-700 leading-6">
            <span className="font-bold text-gray-900">
              {name}
            </span>{" "}
            {title}
          </p>

          <div className="flex items-center justify-between gap-3 mt-2">
            <span className="text-[11px] bg-green-50 text-green-700 px-2 py-1 rounded-md font-medium">
              {status}
            </span>

            <span className="text-[11px] text-gray-400">
              {timeAgo}
            </span>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ActivityCient;