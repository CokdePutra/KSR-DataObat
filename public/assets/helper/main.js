function generateMedicineCode(prefix = "MED") {
    var uniqueIdentifier = Math.random().toString(36).substr(2, 6);
    var medicineCode = `${prefix}${uniqueIdentifier}`;
    return medicineCode;
}
