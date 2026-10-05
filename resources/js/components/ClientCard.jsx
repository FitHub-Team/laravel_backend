import { MoreVertical, AlertTriangle, Phone, Eye } from "lucide-react";
import Button from "./common/Button";
function ClientCard({
    name,
    image,
    progress,
    status,
    statusColor,
    reason,
    timeAgo,
    action,
}) {
    return (
        <div className="bg-white p-1 flex flex-col rounded-xl border border-gray-100 shadow-sm mb-4">
            <div className="flex justify-between items-start mb-3">
                {/* معلومات المشترك */}
                <div className="flex flex-col gap-3">
                    {/* الصورة + الاسم + الحالة */}
                    <div className="flex gap-3">
                        <img
                            src={image}
                            alt={name}
                            className="w-12 h-12 rounded-full object-cover"
                        />

                        <div className="flex items-center gap-1">
                            <h4 className="font-bold text-gray-800">{name}</h4>

                            <span className="text-xs px-1 py-1 mx-2 rounded-md border font-bold border-[#FEE68599] bg-[#FEF3C6] text-[#CA8A04]">
                                متابعة مطلوبة
                            </span>
                        </div>
                    </div>

                    {/* سبب التنبيه */}
                    <div className="flex items-start gap-2 bg-[#FFFBEBCC] border-r-4 border-r-[#FACC15]   px-2">
                        <p className="text-xs text-[#DC2626] leading-relaxed py-1">
                            {" "}
                            <span className="font-bold">السبب: </span> {reason}
                        </p>
                    </div>
                </div>

                {/* نسبة الالتزام والإجراء */}
                <div className="w-40">
                    <div className="flex justify-between text-xs mb-1">
                        <span className="text-gray-500">نسبة الالتزام</span>

                        <span className="font-bold text-gray-700">
                            {progress}%
                        </span>
                    </div>

                    {/* Progress bar */}
                    <div className="w-full bg-gray-100 rounded-full h-1.5">
                        <div
                            className={`h-1.5 rounded-full ${
                                progress > 60 ? "bg-green-500" : "bg-red-500"
                            }`}
                            style={{ width: `${progress}%` }}
                        ></div>
                    </div>

                    {/* Action */}
                    <div className="text-xs mt-3 font-bold text-[#184159]">
                        {action}
                    </div>

                    {/* Button */}
                    <div className="flex mt-7 items-center gap-3">
                        {/* <button className="w-9 h-9 flex items-center  justify-center bg-gray-50 text-gray-600
             rounded-xl  hover:bg-gray-100">
              <MessageSquare size={18} />
            </button> */}
                        <Button
                            title="عرض الملف "
                            Icon={Eye}
                            color="bg-[#407437] text-white font-bold"
                            className="ml-5"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
export default ClientCard;
