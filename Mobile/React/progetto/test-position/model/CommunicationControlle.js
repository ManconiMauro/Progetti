export default class CommunicationController { 
    static BASE_URL = "https://develop.ewlab.di.unimi.it/mc/2425/"; 
    static sid = "mQ9ov2BKS9VWFCZhWiS9dPu2645MylzzuFBLk9ktJGMxkuxSVZD2o2A7VhxOQIrJ";
    static uid = 35043;
    
    static async genericRequest(endpoint, verb, queryParams, bodyParams) { 
    const queryParamsFormatted = new URLSearchParams(queryParams).toString(); 
    const url = this.BASE_URL + endpoint + "?" + queryParamsFormatted; 
    console.log("sending " + verb + " request to: " + url); 
    let fatchData = {method: verb, 
    headers: { 
    Accept: 'application/json', 
    'Content-Type': 'application/json'
    } 
    }; 
    if (verb != 'GET') { 
    fatchData.body = JSON.stringify(bodyParams); 
    } 
    let httpResponse = await fetch(url, fatchData); 
    
    const status = httpResponse.status; 
    if (status == 200) { 
    let deserializedObject = await httpResponse.json(); 
    return deserializedObject; 
    } else { 
    //console.log(httpResponse);
    const message = await httpResponse.text(); 
    let error = new Error("Error message from the server. HTTP status: " + status + " " + message); 
    throw error; 
    } 
    }

    static async genericGetRequest(endpoint, queryParams){
        return await this.genericRequest(endpoint, 'GET', queryParams)
    }

    static async genericPostRequest(endpoint, queryParams, bodyParams){
        return await this.genericRequest(endpoint, 'POST', queryParams, bodyParams)
    }

    static async buyMenu(mid, userLocation){
        console.log("Sono dentro buyMenu")
        let endpoint = "menu/"+mid+"/buy/"
        let queryParams = {}
        let bodyParams = {sid: this.sid, deliveryLocation: {lat: userLocation.latitude, lng:userLocation.longitude}}
        console.log("buyMenu called with endpoint: ",endpoint, " and queryParams: ",queryParams, " and bodyParams: ", bodyParams)
        let order = {}
        try {
            order = await this.genericPostRequest(endpoint, queryParams, bodyParams)
            console.log(order)
        } catch (error) {
            console.log(error)
        }
        return order
    }

    static async getOrder(oid){
        let endpoint = "order/"+oid
        let queryParams = {sid: this.sid}
        console.log("getOrder called with endpoint: ",endpoint, " and queryParams: ",queryParams)
        return await this.genericGetRequest(endpoint, queryParams)
    }

    static async getUser(){
        let endpoint = "user/"+this.uid
        let queryParams = {sid: this.sid}
        console.log("getUser called with endpoint: ",endpoint, " and queryParams: ",queryParams)
        return await this.genericGetRequest(endpoint, queryParams)
    }
}