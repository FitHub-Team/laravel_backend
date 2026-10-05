import { Search, SlidersHorizontal } from "lucide-react";
import FilterPill from "./FilterPill";

import {
  translateCategory,
  translateDifficulty,
} from "../../../utils/exerciseTranslations";

const ExerciseFilters = ({
  search,
  onSearchChange,
  categoryFilter,
  onCategoryChange,
  difficultyFilter,
  onDifficultyChange,
  categories,
  difficulties,
  resultsCount,
}) => {
  const hasActiveFilters =
    search || categoryFilter || difficultyFilter;

  const resetFilters = () => {
    onSearchChange("");
    onCategoryChange("");
    onDifficultyChange("");
  };

  return (
    <div className="px-6 py-4 border-b border-green-100">
      <div className="flex flex-wrap items-center gap-3">
        <div className="relative flex-1 min-w-[220px]">
          <Search
            size={16}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-green-500"
          />

          <input
            type="text"
            value={search}
            onChange={(e) => onSearchChange(e.target.value)}
            placeholder="ابحث باسم التمرين..."
            className="
              w-full
              h-[44px]
              rounded-full
              border
              border-green-100
              bg-green-50/50
              pr-11
              pl-4
              text-sm
              text-green-900
              outline-none
              transition-all
              placeholder:text-[#314158]
              focus:border-green-400
              focus:bg-white
              focus:ring-4
              focus:ring-green-500/10
            "
          />
        </div>

        <button
          type="button"
          onClick={resetFilters}
          disabled={!hasActiveFilters}
          className={`h-[44px] px-4 rounded-full text-sm font-semibold flex items-center gap-2 transition-all ${
            hasActiveFilters
              ? "bg-green-50 text-green-700 hover:bg-green-100"
              : "bg-green-50/40 text-green-800 cursor-not-allowed"
          }`}
          title="إعادة ضبط الفلاتر"
        >
          <SlidersHorizontal size={15} />
          إعادة ضبط
        </button>
      </div>

      <div className="flex flex-wrap items-center gap-2 mt-3">
        <FilterPill
          active={!categoryFilter}
          onClick={() => onCategoryChange("")}
        >
          كل التصنيفات
        </FilterPill>

        {categories.map((category) => (
          <FilterPill
            key={category}
            active={categoryFilter === category}
            onClick={() => onCategoryChange(category)}
          >
            {translateCategory(category)}
          </FilterPill>
        ))}

        <div className="w-px h-5 bg-green-800 mx-1" />

        {difficulties.map((difficulty) => (
          <FilterPill
            key={difficulty}
            active={difficultyFilter === difficulty}
            onClick={() =>
              onDifficultyChange(
                difficultyFilter === difficulty ? "" : difficulty
              )
            }
            variant="soft"
          >
            {translateDifficulty(difficulty)}
          </FilterPill>
        ))}
      </div>

      <p className="text-[11px] text-[#45556C] mt-3">
        {resultsCount} تمرين متوفر
      </p>
    </div>
  );
};

export default ExerciseFilters;