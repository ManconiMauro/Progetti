package com.example.progetto.database.dao

import androidx.room.*
import com.example.progetto.database.models.MenuImage

@Dao
interface MenuImageDao {

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertMenuImage(menuImage: MenuImage)

    @Query("SELECT * FROM menu_images WHERE mid = :mid")
    suspend fun getMenuImage(mid: Int): MenuImage?
}
