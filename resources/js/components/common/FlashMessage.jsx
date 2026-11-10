import { useEffect, useRef } from "react";
import toast from "../../services/toast";

// Keep existing callers compatible while all messages use the global toast stack.
export default function FlashMessage({ message, type = "success", onClose }) {
    const close = useRef(onClose);
    close.current = onClose;

    useEffect(() => {
        if (!message) return;
        toast.show(message, type);
        close.current?.();
    }, [message, type]);

    return null;
}
