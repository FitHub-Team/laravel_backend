import { Navigate, Route, Routes } from "react-router-dom";

// Pages
import Login from "../pages/auth/Login";
import Register from "../pages/auth/Register";
import Dashboard from "../pages/dashboard/Dashboard";

import SubscribersTable from "../pages/coach/trainees/SubscribersTable";
import SubscribersRequset from "../pages/coach/trainees/SubscribersRequset";

import WorkoutPlan from "../pages/coach/workout/WorkoutPlan";
import CoachProfile from "../pages/coach/profile/CoachProfile";
import CustomExercises from "../pages/coach/workout/CustomExercises";
import ChallengesPage from "../pages/coach/challenges/ChallengesPage";

import TraineeProfile from "../pages/coach/trainees/TraineeProfile";
import RecipeLibrary from "../pages/coach/recipes/RecipeLibrary";
import WorkoutPlanVersions from "../pages/coach/Versions/WorkoutPlanVersions";
import CurrentPlansPage from "../pages/coach/workout/CurrentPlansPage";
import EditWorkoutPlan from "../pages/coach/workout/EditWorkoutPlan";
import WorkoutPlanDetails from "../pages/coach/workout/WorkoutPlanDetails";
import TraineeVersionsPage from "../pages/coach/Versions/TraineeVersionsPage";
import NutritionPlanPage from "../pages/coach/nutritionPlan/NutritionPlanPage";
import VersionDetailsPage from "../pages/coach/Versions/VersionDetailsPage";

// Components
import ProtectedRoute from "../components/common/ProtectedRoute";
import DashboardLayout from "../layouts/DashboardLayout";

const AppRoutes = () => {
    return (
        <Routes>
            {/* Default route */}
            <Route path="/" element={<Navigate to="/login" replace />} />

            {/* Auth */}
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />

            {/* Protected Dashboard */}
            <Route
                path="/dashboard"
                element={
                    <ProtectedRoute>
                        <DashboardLayout />
                    </ProtectedRoute>
                }
            >
                {/* Dashboard */}
                <Route index element={<Dashboard />} />
                {/* Trainees */}
                <Route path="subscribers" element={<SubscribersTable />} />
                <Route
                    path="subscribers-request"
                    element={<SubscribersRequset />}
                />
                <Route path="trainees/:id" element={<TraineeProfile />} />
                {/* Workout */}
                <Route path="workout-plan" element={<WorkoutPlan />} />
                {/* Current Plans */}
                <Route path="current-plans" element={<CurrentPlansPage />} />
                {/* View Plan */}
                <Route
                    path="workout-plan/:planId"
                    element={<WorkoutPlanDetails />}
                />
                {/* Edit Plan */}
                <Route
                    path="workout-plan/:planId/edit"
                    element={<EditWorkoutPlan />}
                />
                {/* Custom Exercises */}
                <Route path="exercises" element={<CustomExercises />} />
                {/* Coach Profile */}
                <Route path="coach/profile" element={<CoachProfile />} />
                {/* Recipes */}
                <Route path="recipes" element={<RecipeLibrary />} />
                {/* Versions */}
                <Route path="versions" element={<WorkoutPlanVersions />} />
                <Route
                    path="versions/:traineeId"
                    element={<TraineeVersionsPage />}
                />
                <Route
                    path="/dashboard/versions/:traineeId/:planId"
                    element={<VersionDetailsPage />}
                />
                {/* Challenges */}
                <Route path="challenges" element={<ChallengesPage />} />
                <Route path="nutrition-plan" element={<NutritionPlanPage />} />
            </Route>
        </Routes>
    );
};

export default AppRoutes;
