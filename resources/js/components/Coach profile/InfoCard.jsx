function InfoCard({ icon, label, value }) {
  return (
    <div className="flex items-center gap-3 p-4 rounded-xl border border-gray-100 bg-[#F8F9FA] hover:border-[#7FA279] transition-colors">
      <div className="w-10 h-10 rounded-lg bg-white flex items-center justify-center shrink-0 text-[#7FA279] border border-gray-100">
        {icon}
      </div>
      <div className="flex-1 min-w-0">
        <p className="text-xs text-gray-400 mb-0.5">{label}</p>
        <p className="text-sm font-semibold text-[#1A4762] truncate">
          {value ?? "—"}
        </p>
      </div>
    </div>
  );
}
export default InfoCard;