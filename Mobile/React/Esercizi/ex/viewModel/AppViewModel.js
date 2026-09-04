import CommunicationController from "../model/CommunicationController";

export async function fetchData() {
    let user;
    try {
        console.log("Attempting API call");
        user = await CommunicationController.getUser();
    } catch (error) {
        console.error("Error during call:", error);
        return null;
    }
    console.log(user);
    return user;
}
