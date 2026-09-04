import CommunicationController from "../model/CommunicationController";

export async function fetchData(oid){
    let order = undefined
    try{
        order = await CommunicationController.getOrder(oid);
    } catch(error){
        return "Error during call: "+error
    }
    let status = order.status
    console.log("status: "+ status)
    if(status == "COMPLETED"){
      return "Book was delivered";
    }
    let deliveryDate = undefined
    try{
        deliveryDate = await CommunicationController.getObjectDeliveryDate(oid);
    } catch(error){
        return "Error during call: "+error
    }
    const date = new Date(deliveryDate.date)
    const day = date.getUTCDay();
    const month = date.getUTCMonth();
    const year = date.getUTCFullYear();
    return "Delivery Expected: "+day+"/"+month+"/"+year
}