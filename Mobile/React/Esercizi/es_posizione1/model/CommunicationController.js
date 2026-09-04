export default class CommunicationController { 
    static BASE_URL = "https://develop.ewlab.di.unimi.it/mc/2425/"; 
    static sid = "iKg95XS8I9wRdvarj0zhWODyRrttocdFvvk1X3e4doFmOpUmDsghHeTFUpa5Z5gT";
    static uid = 34980;
    
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

    static async getmenu(position){
        let endpoint = "menu/"+this.uid
        let queryParams = {sid: this.sid}
        console.log("getUser called with endpoint: ",endpoint, " and queryParams: ",queryParams)
        return await this.genericGetRequest(endpoint, queryParams)
    }

}