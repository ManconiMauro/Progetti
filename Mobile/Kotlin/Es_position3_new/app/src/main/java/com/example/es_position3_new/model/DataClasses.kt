package com.example.es_position3_new.model

import kotlinx.serialization.Serializable

@Serializable
data class User(
    val firstName: String,
    val lastName: String,
    val cardFullName: String,
    val cardNumber: String,
    val cardExpireMonth: Int,
    val cardExpireYear: Int,
    val cardCVV: String,
    val uid: Int,
    val lastOid: Int?,
    val orderStatus: String?
)
@Serializable
data class UserResponse(
    val sid: String,
    val uid: Int
)

@Serializable
data class Menu (
    val mid : Int,
    val name : String,
    val price : Double,
    val location : Location,
    val imageVersion : Int,
    val shortDescription : String,
    val deliveryTime : Int
)

@Serializable
data class ResponseError(val message: String)

@Serializable
data class PostOrderRequest(
    val sid: String,
    val deliveryLocation: Location
)

@Serializable
data class Location(
    val lat: Double,
    val lng: Double
)

@Serializable
data class Order(
    val oid: Int,
    val mid: Int,
    val uid: Int,
    val creationTimestamp: String,
    val status: String,
    val deliveryLocation: Location,
    val expectedDeliveryTimestamp: String,
    val currentPosition: Location
)
