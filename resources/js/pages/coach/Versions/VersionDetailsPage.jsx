import { useNavigate, useParams } from "react-router-dom";
import VersionDetailsContent from "./VersionDetailsContent";

export default function VersionDetailsPage() {
    const { traineeId, planId } = useParams();
    const navigate = useNavigate();

    return (
        <main className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <VersionDetailsContent
                traineeId={traineeId}
                planId={planId}
                onBack={() =>
                    navigate(`/dashboard/versions/${traineeId}`)
                }
            />
        </main>
    );
}