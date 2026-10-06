import {
 FileText
} from "lucide-react";
function EmptyState({ text }) {
  return (
    <div className="col-span-full flex flex-col items-center justify-center py-10 text-center w-full">
      <div className="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
        <FileText size={24} className="text-gray-400" />
      </div>
      <p className="text-sm text-gray-400">{text}</p>
    </div>
  );
}
export default EmptyState;
