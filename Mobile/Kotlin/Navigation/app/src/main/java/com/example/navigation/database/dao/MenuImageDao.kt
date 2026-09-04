package com.example.navigation.database.dao

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.example.navigation.database.models.MenuImage

@Dao
interface MenuImageDao {

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertMenuImage(menuImage: MenuImage)

    @Query("SELECT * FROM menu_images WHERE mid = :mid")
    suspend fun getMenuImage(mid: Int): MenuImage?
}