export default class UniqueId {
  static generate(prefix = "", moreEntropy = false): string {
    const now = Date.now(); // Get current timestamp in milliseconds
    let uniqueId = prefix + now.toString(36); // Convert timestamp to base 36 (alphanumeric)

    if (moreEntropy) {
      uniqueId += Math.random().toString(36).substring(2, 8); // Append random string for uniqueness
    } else {
      uniqueId += Math.floor(Math.random() * 1000000).toString(36); // Append random number
    }

    return uniqueId;
  }
}
