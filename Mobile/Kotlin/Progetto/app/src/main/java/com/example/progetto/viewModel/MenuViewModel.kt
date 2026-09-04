package com.example.progetto.viewModel

import android.content.Context
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.progetto.api.CommunicationController
import com.example.progetto.database.DatabaseHelper
import com.example.progetto.model.Menu
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

class MenuViewModel(private val context: Context) : ViewModel() {

    private val _menuList = MutableStateFlow<List<Menu>>(emptyList())
    val menuList: StateFlow<List<Menu>> = _menuList

    private val _isLoading = MutableStateFlow(true)
    val isLoading: StateFlow<Boolean> = _isLoading

    private val _errorMessage = MutableStateFlow<String?>(null)
    val errorMessage: StateFlow<String?> = _errorMessage

    init {
        if (_menuList.value.isEmpty()) {
            loadMenus()
        }
    }

    private fun loadMenus() {
        viewModelScope.launch {
            _errorMessage.value = null
            try {
                CommunicationController.initializeSID(context)
                val menus = CommunicationController.getMenu(context) ?: emptyList()
                _menuList.value = menus

                menus.forEach { menu ->
                    loadImageForMenu(menu)
                }
            } catch (e: Exception) {
                _errorMessage.value = "Errore durante il caricamento: ${e.message}"
            } finally {
                _isLoading.value = false
            }
        }
    }

    private suspend fun loadImageForMenu(menu: Menu) {
        val menuImageDao = DatabaseHelper.getDatabase(context).menuImageDao()
        val existingImage = menuImageDao.getMenuImage(menu.mid)

        if (existingImage == null) {
            CommunicationController.getImageAndSaveToDB(menu.mid, menu.imageVersion, context)
        }
    }

    fun getMenuById(menuId: Int): Menu? {
        return menuList.value.find { it.mid == menuId }
    }
}
