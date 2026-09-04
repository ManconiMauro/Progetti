import CommunicationController from "../model/CommunicationControlle";

export default class viewModel{
    static async orderMenu(userLocation){
        await CommunicationController.buyMenu(12, userLocation);
    }

    static async fetchMenu(oid){
        let order = await CommunicationController.getOrder(oid)
    }
}