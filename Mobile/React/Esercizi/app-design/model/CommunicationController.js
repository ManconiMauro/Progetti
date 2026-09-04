export default class CommunicationController { 
    static BASE_URL = "https://develop.ewlab.di.unimi.it/mc/2425/"; 
    static sid = "r37g1ve1IcbIBw8zPjR4MEEUftxkE5C7s1nj0s263SezIcn9bksuDaJwVHS5ybp0";
    static uid = 79;
    
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

    static async genericPutRequest(endpoint, queryParams, bodyParams){
        return await this.genericRequest(endpoint, 'PUT', queryParams, bodyParams)
    }

    static async getUser(){
        let endpoint = "user/"+this.uid
        let queryParams = {sid: this.sid}
        console.log("getUser called with endpoint: ",endpoint, " and queryParams: ",queryParams)
        return await this.genericGetRequest(endpoint, queryParams)
    }

    static async putUser(userInfo){
        let endpoint = "user/"+this.uid
        let queryParams = {sid: this.sid}
        let bodyParams = { ...userInfo, sid: this.sid};
        console.log("putUser called with endpoint: ",endpoint, " and queryParams: ",queryParams, " and bodyParams:",bodyParams)
        return await this.genericPutRequest(endpoint, queryParams, bodyParams)
    }

}