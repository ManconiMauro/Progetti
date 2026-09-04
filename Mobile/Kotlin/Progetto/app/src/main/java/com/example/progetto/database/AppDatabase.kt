package com.example.progetto.database

import androidx.room.Database
import androidx.room.RoomDatabase
import com.example.progetto.database.dao.MenuImageDao
import com.example.progetto.database.models.MenuImage

@Database(entities = [MenuImage::class], version = 1)
abstract class AppDatabase : RoomDatabase() {
    abstract fun menuImageDao(): MenuImageDao
}
