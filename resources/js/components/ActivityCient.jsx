function ActivityCient({ image, title, status, timeAgo }) {
  return (
    <div className="border-b border-gray-300 m-5 py-1">
      <div className="flex gap-2 ">
        {/* image */}
        <div className="border border-gray-600 rounded-full w-10 h-10">
          <img src="{image}" alt="محمد" />
        </div>
        <div className="flex flex-col">
          <p className="text-sm text-[#1A4762]">{title}</p>
          <div className="mt-2">
            {" "}
            <span className="px-2 py-1  bg-[#ECF1EB] text-[#407437] rounded w-100 text-xs">
              {status}
            </span>
          </div>
        </div>
        
      </div>
      <div className="text-end">
          <p className="text-xs text-[#1A4762]">{timeAgo}</p>
        </div>
    </div>
  );
}
export default ActivityCient;
