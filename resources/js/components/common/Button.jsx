function Button({ title, Icon, color, className = "", onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`flex items-center justify-center gap-1 ${color} px-4 py-2 rounded-lg text-sm hover:opacity-90 transition whitespace-nowrap cursor-pointer ${className}`}
    >
      {Icon && <Icon size={16} />}
      {title && <span>{title}</span>}
    </button>
  );
}

export default Button;