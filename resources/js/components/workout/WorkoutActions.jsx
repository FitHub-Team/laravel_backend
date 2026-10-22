import { Clock, Dumbbell, Utensils } from "lucide-react";
import { useNavigate } from "react-router-dom";

import Button from "../common/Button";

const Actions = ({
    activeSection,
    onSectionChange,
}) => {
    const navigate = useNavigate();

    return (
        <div className="rounded-xl bg-white p-4 shadow-sm">
            <div className="flex flex-wrap items-center gap-3">

                {/* Workout */}
                <Button
                    title="إنشاء خطة التمارين الأسبوعية"
                    Icon={Dumbbell}
                    color={
                        activeSection === "workout"
                            ? "bg-[#407437] text-white"
                            : "text-gray-700"
                    }
                    className={
                        activeSection === "workout"
                            ? ""
                            : "border border-gray-200"
                    }
                    onClick={() => {
                        onSectionChange("workout");
                        navigate("/dashboard/workout-plan");
                    }}
                />

                {/* Nutrition */}
                <Button
                    title="الخطة الغذائية والماكروز"
                    Icon={Utensils}
                    color={
                        activeSection === "nutrition"
                            ? "bg-[#407437] text-white"
                            : "text-gray-700"
                    }
                    className={
                        activeSection === "nutrition"
                            ? ""
                            : "border border-gray-200"
                    }
                    onClick={() => {
                        navigate("/dashboard/nutrition-plan");
                    }}
                />

                {/* Versions */}
                <Button
                    title="الإصدارات السابقة"
                    Icon={Clock}
                    color={
                        activeSection === "versions"
                            ? "bg-[#407437] text-white"
                            : "text-gray-700"
                    }
                    className={
                        activeSection === "versions"
                            ? ""
                            : "border border-gray-200"
                    }
                    onClick={() => {
                        navigate("/dashboard/versions");
                    }}
                />
            </div>
        </div>
    );
};

export default Actions;