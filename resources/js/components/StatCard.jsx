function StatCard({ title, value, icon , subtitle, color ,note , textColor , NoteColor }) {
  return (
     <div className="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-full">
    <div className="flex justify-between items-start mb-4">
        <h6 className={` font-semibold text-sm mb-1 ${textColor}`}>{title}</h6>
      <div className={`p-2 rounded-lg  ${color} `} >
        {icon}
      </div>
    </div>
    <div>
      <div className="flex justify-between items-center">
      <span className="text-2xl font-bold text-gray-800">{value}</span>
      <div className="flex justify-between  items-center mt-2">
      <span className={`text-xs ${NoteColor} p-1 rounded-lg font-semibold`}>{note}</span>
      </div>
      </div>
      <p className="text-xs text-gray-500">{subtitle}</p>
    </div>
  </div>
  );
}

export default StatCard;