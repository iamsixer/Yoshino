package yoshino.listener;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.ApplicationListener;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.authentication.event.AuthenticationSuccessEvent;
import org.springframework.security.oauth2.provider.OAuth2Authentication;
import org.springframework.stereotype.Component;
import yoshino.models.User;
import yoshino.repositories.UserRepository;

import java.util.Date;
import java.util.Map;

/**
 * Created by Volio on 2017/1/6.
 * 监听登录成功事件
 */
@Component
public class OAuthSuccessListener implements ApplicationListener<AuthenticationSuccessEvent> {

    private final UserRepository userRepository;

    @Autowired
    public OAuthSuccessListener(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public void onApplicationEvent(AuthenticationSuccessEvent event) {
        OAuth2Authentication oAuth2Authentication = (OAuth2Authentication) event.getAuthentication();
        UsernamePasswordAuthenticationToken userAuthentication = (UsernamePasswordAuthenticationToken) oAuth2Authentication.getUserAuthentication();
        Map userDetails = (Map) userAuthentication.getDetails();
        findLocalUser(userDetails);
    }

    private User findLocalUser(Map userDetails) {
        User user = userRepository.findOneByUid(Long.parseLong((String) userDetails.get("id")));

        User rUser = new User();
        rUser.setUid(Long.parseLong((String) userDetails.get("id")));
        rUser.setUsername((String) userDetails.get("login"));
        rUser.setEmail((String) userDetails.get("email"));
        rUser.setAvatar((String) userDetails.get("avatar"));
        rUser.setRole((String) userDetails.get("role"));
        rUser.setCreatedAt(new Date());
        rUser.setStreamer(false);

        if (user == null) {
            userRepository.save(rUser);
        } else {
            if (!user.equals(rUser)) {
                user.setUsername(rUser.getUsername());
                user.setEmail(rUser.getEmail());
                user.setAvatar(rUser.getAvatar());
                userRepository.save(user);
            }
        }

        return user;
    }
}