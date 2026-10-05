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
                <Route
                    path="subscribers"
                    element={<SubscribersTable />}
                />

                <Route
                    path="subscribers-request"
                    element={<SubscribersRequset />}
                />

                {/* Workout */}
                <Route
                    path="workout-plan"
                    element={<WorkoutPlan />}
                />

                {/* Custom Exercises */}
                <Route
                    path="exercises"
                    element={<CustomExercises />}
                />

                {/* Coach Profile */}
                <Route
                    path="coach/profile"
                    element={<CoachProfile />}
                />
            </Route>
        </Routes>
    );
};

export default AppRoutes;
