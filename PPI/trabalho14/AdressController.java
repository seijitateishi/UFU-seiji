package com.example.demo;

import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RestController;
import java.util.List;
import java.util.ArrayList;
import java.util.Arrays;

@RestController
public class AddressController {

    private final List<Address> addresses = new ArrayList<>(
            Arrays.asList(
                    new Address("38400100", "Floriano Peixoto", "Centro", "Uberlândia"),
                    new Address("38400200", "Tiradentes", "Fundinho", "Uberlândia"),
                    new Address("38400300", "Lions Clube", "Osvaldo Rezende", "Uberlândia")
            )
    );

    @GetMapping("/hello")
    public String helloWorld() {
        return "Hello World!";
    }

    @GetMapping("/address")
    public List<Address> getAddresses() {
        return this.addresses;
    }

    @GetMapping("/address/{cep}")
    public ResponseEntity<Address> getAddress(@PathVariable String cep) {
        for (Address address : this.addresses) {
            if (address.getCep().equals(cep)) {
                return ResponseEntity.ok(address);
            }
        }
        return ResponseEntity.notFound().build();
    }

    @PostMapping("/address")
    public void addAddress(@RequestBody Address address) {
        this.addresses.add(address);
    }

    @DeleteMapping("/address/{cep}")
    public ResponseEntity<Void> deleteAddress(@PathVariable String cep) {
        for (int i = 0; i < addresses.size(); i++) {
            if (addresses.get(i).getCep().equals(cep)) {
                addresses.remove(i);
                return ResponseEntity.ok().build();
            }
        }
        return ResponseEntity.notFound().build();
    }
}