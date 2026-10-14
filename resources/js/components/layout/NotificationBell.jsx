import { useState } from "react";
import { Bell } from "lucide-react";

function NotificationBell() {

    const [isOpen, setIsOpen] = useState(false);

    return (
        <div className="relative">
            <button
                onClick={() => setIsOpen(!isOpen)}
                className="p-2 text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 relative"
            >
                <Bell size={20} />

                <span className="absolute top-1 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </button>

            {isOpen && (
                <div className="absolute left-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg p-4 z-50">
                    <h3 className="font-semibold text-gray-800 mb-3">
                        الاشعارات 
                    </h3>

                    <div className="text-sm text-gray-600">
                        تمتلك اشعارات جديدة 
                    </div>
                </div>
            )}
        </div>
    );
}

export default NotificationBell;