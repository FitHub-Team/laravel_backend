import api from "./api";

/*
|--------------------------------------------------------------------------
| Pending subscription requests
|--------------------------------------------------------------------------
*/

export const getPendingSubscriptionRequests = async () => {
  const response = await api.get(
    "/coach/subscriptions/pending",
  );

  return response.data;
};

/*
|--------------------------------------------------------------------------
| Accept subscription
|--------------------------------------------------------------------------
*/

export const acceptSubscriptionRequest = async (
  subscriptionId,
) => {
  const response = await api.put(
    `/coach/subscriptions/${subscriptionId}/accept`,
  );

  return response.data;
};

/*
|--------------------------------------------------------------------------
| Reject subscription
|--------------------------------------------------------------------------
*/

export const rejectSubscriptionRequest = async (
  subscriptionId,
) => {
  const response = await api.put(
    `/coach/subscriptions/${subscriptionId}/reject`,
  );

  return response.data;
};

/*
|--------------------------------------------------------------------------
| Accepted trainees
|--------------------------------------------------------------------------
*/

export const getDashboardTrainees = async () => {
  const response = await api.get("/coach/trainees", {
    params: {
      search: "",
    },
  });

  return response.data;
};