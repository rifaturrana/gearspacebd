import "../../components/CartItem";
import "../../components/LandscapeProducts";

Alpine.data("Cart", () => ({
    shippingMethodName: null,

    get cartFetched() {
        return this.$store.cart.fetched;
    },

    get cartIsEmpty() {
        return this.$store.cart.isEmpty;
    },

    init() {
        console.log('Cart component initialized');
        Alpine.effect(() => {
            if (this.cartFetched) {
                this.hideSkeleton();
            }
        });
    },

    hideSkeleton() {
        document.querySelector(".cart-skeleton").remove();
    },

    clearCart() {
        this.$store.cart.clearCart();

        axios
            .delete("/cart/clear")
            .then(({ data }) => {
                this.$store.cart.updateCart(data);
            })
            .catch((error) => {
                notify(error.response.data.message);
            });
    },

    testFunction() {
        console.log('Test function called');
        alert('Test function works!');
    },

    async updateCart() {
        console.log('Update cart function called');
        const button = document.querySelector('.update-cart-btn');
        const originalContent = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        button.disabled = true;
        
        try {
            const { data } = await axios.get("/cart/get");
            this.$store.cart.updateCart(data);
            notify("Cart updated successfully");
        } catch (error) {
            console.error('Update cart error:', error);
            notify(error.response?.data?.message || "Something went wrong");
        } finally {
            // Restore button state
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    },
}));
