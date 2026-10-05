import { Clock, Dumbbell, Utensils, Sparkles } from "lucide-react";
import Button from "../common/Button";

const Actions = ({
  activeSection,
  onSectionChange,
}) => {
  return (
    <div className="bg-white rounded-xl p-4 shadow-sm">
      <div className="flex justify-between items-center">

        {/* أقسام الخطط */}
        <div className="flex items-center gap-3">

          {/* التمارين */}
          <Button
            title="إنشاء خطة التمارين الأسبوعية"
            Icon={Dumbbell}
            color={
              activeSection === "workout"
                ? "bg-green-700 text-white"
                : "text-gray-700"
            }
            className={
              activeSection === "workout"
                ? ""
                : "border border-gray-200"
            }
            onClick={() => onSectionChange("workout")}
          />

          {/* التغذية */}
          <Button
            title="الخطة الغذائية والماكروز"
            Icon={Utensils}
            color={
              activeSection === "nutrition"
                ? "bg-green-700 text-white"
                : "text-gray-700"
            }
            className={
              activeSection === "nutrition"
                ? ""
                : "border border-gray-200"
            }
            onClick={() => onSectionChange("nutrition")}
          />

          {/* الإصدارات السابقة */}
          <Button
            title="إصدارات الخطة السابقة (4)"
            Icon={Clock}
            color={
              activeSection === "versions"
                ? "bg-green-700 text-white"
                : "text-gray-700"
            }
            className={
              activeSection === "versions"
                ? ""
                : "border border-gray-200"
            }
            onClick={() => onSectionChange("versions")}
          />

        </div>

      

      </div>
    </div>
  );
};

export default Actions;