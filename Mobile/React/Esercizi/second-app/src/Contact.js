import React, { Component } from 'react'; 
class Contact extends React.Component { 
 render() { 
    return <li>
    <h1> {this.props.data.name} </h1>
    <button onClick={() => this.props.onDelete(this.props.data.id)}>delete</button>
    <p>E' il mio amico con il nome di {this.props.data.name.length} caratteri</p>
    </li>; 
    }
 } 
export default Contact ;