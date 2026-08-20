describe("TC-09: Pembayaran Midtrans (US-006)", () => {
  it("TC-09: sistem mengarahkan pengguna ke proses pembayaran Midtrans", () => {
    cy.loginAsCustomer();

    // Pastikan login benar-benar berhasil sebelum melanjutkan
    cy.url({ timeout: 15000 }).should("not.include", "/login");
    cy.wait(1500);

    // Pastikan ada produk di keranjang terlebih dahulu
    cy.visit("/products/cypress-test-chair");
    cy.window().should("have.property", "Livewire");
    cy.get('button[wire\\:click="addToCart"]', { timeout: 15000 }).should("be.visible");
    cy.wait(1500);
    cy.get('button[wire\\:click="addToCart"]').click();
    cy.wait(2500);

    // Isi form checkout
    cy.visit("/checkout");

    // Jika sempat ter-redirect ke login (sesi tidak stabil), login ulang.
    cy.url({ timeout: 10000 }).then((url) => {
      if (url.includes("/login")) {
        cy.get('input[wire\\:model="email"]').type("customer@cypress.test");
        cy.get('input[wire\\:model="password"]').type("password123");
        cy.contains("button", "Sign in").click();
        cy.wait(2000);
        cy.visit("/checkout");
      }
    });

    cy.window().should("have.property", "Livewire");
    cy.get('input[wire\\:model="first_name"]', { timeout: 15000 }).should("be.visible");
    cy.wait(1500);

    cy.get('input[wire\\:model="first_name"]').type("Cypress");
    cy.get('input[wire\\:model="last_name"]').type("Tester");
    cy.get('input[wire\\:model="email"]').clear().type("customer@cypress.test");
    cy.get('input[wire\\:model="phone"]').type("081234567890");

    cy.get('select[wire\\:model\\.live="selectedProvince"]').select("52");
    cy.wait(2000);

    cy.get('select[wire\\:model\\.live="selectedCity"]', { timeout: 10000 }).should("not.be.disabled");
    cy.get('select[wire\\:model\\.live="selectedCity"]').find("option").its("length").should("be.gt", 1);
    cy.get('select[wire\\:model\\.live="selectedCity"]').select(1);
    cy.wait(2000);

    cy.get('select[wire\\:model\\.live="selectedDistrict"]', { timeout: 10000 }).should("not.be.disabled");
    cy.get('select[wire\\:model\\.live="selectedDistrict"]').find("option").its("length").should("be.gt", 1);
    cy.get('select[wire\\:model\\.live="selectedDistrict"]').select(1);
    cy.wait(1000);

    cy.get('textarea[wire\\:model="address"]').type("Jl. Cypress Testing No. 1");
    cy.get('input[wire\\:model="zip_code"]').type("83115");

    cy.wait(1000);

    cy.contains("button", "Place Order").click();
    cy.wait(3000);

    cy.url({ timeout: 15000 }).should("include", "/success");
    cy.get('button[wire\\:click="payNow"]', { timeout: 10000 }).should("be.visible");
  });
});