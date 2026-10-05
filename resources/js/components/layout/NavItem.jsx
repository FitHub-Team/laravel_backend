import { Link } from "react-router-dom";

function NavItem({
  icon,
  label,
  active,
  badge,
  badgeColor = "bg-green-700",
  logoutColor = "",
  onClick,
  to,
}) {
  const content = (
    <>
      <div className="flex items-center gap-3">
        {icon}
        <span className="text-sm font-medium">{label}</span>
      </div>

      {badge && (
        <span
          className={`text-xs text-white ${badgeColor} w-5 h-5 flex items-center justify-center rounded-full`}
        >
          {badge}
        </span>
      )}
    </>
  );

  const className = `flex items-center justify-between w-full p-3 rounded-lg cursor-pointer ${logoutColor} transition-colors ${
    active
      ? "bg-[#407437] text-white"
      : "text-gray-600 hover:bg-gray-50"
  }`;

  // إذا كان عندنا to → انتقال لصفحة
  if (to) {
    return (
      <Link to={to} className={className}>
        {content}
      </Link>
    );
  }

  // إذا ما في to → زر عادي مثل تسجيل الخروج
  return (
    <button onClick={onClick} className={className}>
      {content}
    </button>
  );
}

export default NavItem;