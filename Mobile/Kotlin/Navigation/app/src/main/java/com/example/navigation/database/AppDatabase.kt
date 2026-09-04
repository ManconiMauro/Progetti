package com.example.navigation.database

import androidx.room.Database
import androidx.room.RoomDatabase
import com.example.navigation.database.dao.MenuImageDao
import com.example.navigation.database.models.MenuImage

@Database(entities = [MenuImage::class], version = 1)
abstract class AppDatabase : RoomDatabase() {
    abstract fun menuImageDao(): MenuImageDao
}