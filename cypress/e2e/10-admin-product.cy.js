describe("TC-11: Manajemen Produk - Tambah Produk (US-008)", () => {
  it("TC-11: admin dapat menambahkan produk baru", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/products/create");
    cy.window().should("have.property", "Livewire");
    cy.get("#data\\.name", { timeout: 15000 }).should("be.visible");
    cy.wait(1500);

    const productName = "Cypress Kursi " + Date.now();

    cy.get("#data\\.name").type(productName);
    cy.get("#data\\.name").blur();
    cy.wait(1000);

    cy.get("#data\\.price").type("750000");
    cy.wait(500);
    cy.get("#data\\.stock").type("15");
    cy.wait(500);
    cy.get("#data\\.availability").select("ready");
    cy.wait(500);
    cy.get("#data\\.weight_kg").type("8");
    cy.wait(500);

    cy.get('input[type="file"]').first().selectFile("cypress/fixtures/test-product.jpg", { force: true });

    // Beri waktu cukup panjang agar proses upload gambar benar-benar selesai
    // sebelum mencoba submit, supaya tombol tidak dalam status "processing".
    cy.wait(5000);

    cy.get('button[type="submit"]').first().click({ force: true });
    cy.wait(4000);

    cy.url({ timeout: 20000 }).should("not.include", "/create");
  });
});