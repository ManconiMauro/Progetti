import React, { Component } from 'react'; 
class DetailsScreen extends React.Component { 
 render() { 
    console.log(this.props.piatto)
    return <div>
      <h2> {this.props.piatto.name} </h2>
      <p>{this.props.piatto.longDescription}</p>
      <button onClick={() => this.props.handleDelete()}>Elimina Piatto</button>
      <button onClick={() => this.props.handleBack()}>Indietro</button>
    </div>
 } 
}
export default DetailsScreen;