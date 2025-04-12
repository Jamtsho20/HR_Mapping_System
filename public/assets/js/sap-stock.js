function filterItemsByQuantity(warehouseCodes, data) {
    // Filter the ItemWarehouseInfoCollection to get items with quantity greater than 0
    const filteredItems = data.ItemWarehouseInfoCollection.filter(item =>
        warehouseCodes.includes(item.WarehouseCode) && item.InStock > 0
    );

    return filteredItems;
}
