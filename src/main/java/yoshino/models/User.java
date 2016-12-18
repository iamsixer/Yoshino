package yoshino.models;

import org.hibernate.annotations.ColumnDefault;

import javax.persistence.*;
import javax.validation.constraints.NotNull;
import javax.validation.constraints.Size;

/**
 * Created by Volio on 2016/12/18.
 */
@Entity
@Table(name = "users")
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(name = "username", nullable = false, length = 100, unique = true)
    @NotNull
    @Size(min = 4, max = 100)
    private String username;

    @Column(name = "mail", nullable = false, length = 200, unique = true)
    @NotNull
    @Size(min = 5, max = 200)
    private String mail;

    @Column(name = "password", nullable = false, length = 200)
    @NotNull
    private String password;

    @Column(name = "role", nullable = false, length = 20)
    @ColumnDefault("'USER'")
    private String role;

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getMail() {
        return mail;
    }

    public void setMail(String mail) {
        this.mail = mail;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getRole() {
        return role;
    }

    public void setRole(String role) {
        this.role = role;
    }
}
