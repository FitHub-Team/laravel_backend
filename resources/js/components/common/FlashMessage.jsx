import { useEffect } from "react";
import { CheckCircle, XCircle, AlertTriangle, Info, X } from "lucide-react";

function FlashMessage({ message, type = "success", onClose }) {
  useEffect(() => {
    if (!message) return;

    const timer = setTimeout(() => {
      onClose();
    }, 3000);

    return () => clearTimeout(timer);
  }, [message, onClose]);

  if (!message) return null;

  const styles = {
    success: {
      container: "bg-green-50 border-green-200 text-green-800",
      icon: <CheckCircle className="w-5 h-5 text-green-600" />,
    },

    error: {
      container: "bg-red-50 border-red-200 text-red-800",
      icon: <XCircle className="w-5 h-5 text-red-600" />,
    },

    warning: {
      container: "bg-yellow-50 border-yellow-200 text-yellow-800",
      icon: <AlertTriangle className="w-5 h-5 text-yellow-600" />,
    },

    info: {
      container: "bg-blue-50 border-blue-200 text-blue-800",
      icon: <Info className="w-5 h-5 text-blue-600" />,
    },
  };

  const currentStyle = styles[type];

  return (
    <div
      className={`fixed top-5 right-5 z-50
        flex items-center gap-3
        min-w-[320px] max-w-[450px]
        px-4 py-3
        border rounded-lg
        shadow-md
        ${currentStyle.container}`}
    >
      {currentStyle.icon}

      <p className="flex-1 text-sm font-medium">
        {message}
      </p>

      <button
        onClick={onClose}
        className="text-gray-400 hover:text-gray-600 transition"
      >
        <X className="w-4 h-4" />
      </button>
    </div>
  );
}

export default FlashMessage;