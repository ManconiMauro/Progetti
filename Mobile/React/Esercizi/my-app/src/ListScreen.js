import React, { Component } from 'react'; 
import Dish from './Dish'
class ListScreen extends React.Component { 
 render() { 
    return <div>
      <ul>{this.props.dishes.map(dish => <Dish key={dish.id} data={dish} handleDetails={(dish)=> this.props.handleDetails(dish)}/>)}</ul>
    </div>
 } 
}
export default ListScreen;