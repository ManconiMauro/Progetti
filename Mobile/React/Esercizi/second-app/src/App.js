import React, { Component } from 'react'; 
import Contact from './Contact'
class App extends React.Component { 
 state = { 
 contacts: [{uid: 0, name:"Andrea", pversion:0}, 
 {uid: 1, name:"Bruno", pversion:1}, 
 {uid: 2, name:"Carlo", pversion:2}], 
 } 
 handleDelete = (uid) => { 
  console.log(uid) 
  console.log("Deleting " + uid) 
  let newContacts = this.state.contacts.filter(c => c.uid !== uid) 
  this.setState({ contacts : newContacts}) 
  console.log(this.state) 
  } 

  handleMakeAnonymous = () => { 
    let newContacts = this.state.contacts.map(c => { 
    let res = {} 
    res.id=c.id
    res.name = "anonymous"
    return res
    }) 
    this.setState({ contacts : newContacts}) 
    }

  render() { 
  return <div>
    <button onClick={() => this.handleMakeAnonymous()}>Make all anonymous</button>
    <ul>{this.state.contacts.map(contact => <Contact key={contact.uid} data={contact} onDelete={this.handleDelete}/>)}</ul>
  </div>
  }
} 
export default App;

