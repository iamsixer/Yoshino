package yoshino.controllers.api.user;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import yoshino.models.User;
import yoshino.services.UserService;

import java.security.Principal;
import java.util.Map;

/**
 * Created by Volio on 2017/1/9.
 */
@RestController
@RequestMapping("/api/user")
public class UserApiController {

    private final UserService userService;

    @Autowired
    public UserApiController(UserService userService) {
        this.userService = userService;
    }

    @GetMapping
    public User getUserInfo(Principal principal) {
        return userService.getUserInfo(principal.getName());
    }
}
