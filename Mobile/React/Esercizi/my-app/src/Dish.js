import React, { Component } from 'react'; 
class Dish extends React.Component { 
 render() { 
    return <li key={this.props.data.id}>
    <h2> {this.props.data.name} </h2>
    <p> {this.props.data.shortDescription}</p>
    <button onClick={() => this.props.handleDetails(this.props.data)}>Dettagli</button>
    </li>
    }
 } 
export default Dish ;